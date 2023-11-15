<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Captcha
 *
 * @author      Zencaptcha
 * @copyright   Zencaptcha
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\HTML\HTMLHelper;


class PlgCaptchaZencaptcha extends CMSPlugin
{

    protected $app;
    protected $autoloadLanguage = true;

    public function onPrivacyCollectAdminCapabilities()
    {
        $this->loadLanguage();
    }


    public function onInit()
    {
  
        if ($this->params->get('site_key', '') === '') {
            throw new \RuntimeException(Text::_('PLG_CAPTCHA_ZENCAPTCHA_ERROR_SITEKEY_MISSING'));
        }

       $this->app->getDocument()->getWebAssetManager()
        ->registerAndUseScript('plg_captcha_zencaptcha.api', 'https://www.zencaptcha.com/captcha/widget.js', [], ['onload' => 'zencaptchaInit()', 'async' => true, 'defer' => true]);
        return true;
    }


    public function onDisplay($name = null, $id = 'zenc-captcha', $class = '')
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $ele = $dom->createElement('div');
        $ele->setAttribute('id', $id);
        $ele->setAttribute('class', 'zenc-captcha');
        $ele->setAttribute('data-sitekey', $this->params->get('site_key', ''));
        $ele->setAttribute('data-start', $this->params->get('verification_method', 'none'));

        $dom->appendChild($ele);

        return $dom->saveHTML($ele);
    }


    public static function translate_zen($key)
    {
        $preferred_lang_code = 'en';
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $user_langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $preferred_lang = strtok($user_langs[0], ';');
            $preferred_lang_code = substr($preferred_lang, 0, 2);
        }

        $translations = [
            'Use a valid email address.' => [
                'en' => 'Use a valid email address.',
                'de' => 'Verwenden Sie eine gültige E-Mail-Adresse.',
                'fr' => 'Utilisez une adresse e-mail valide.',
                'bg' => 'Използвайте валиден имейл адрес.',
                'ca' => 'Utilitzeu una adreça de correu electrònic vàlida.',
                'cs' => 'Použijte platnou e-mailovou adresu.',
                'da' => 'Brug en gyldig e-mailadresse.',
                'el' => 'Χρησιμοποιήστε μια έγκυρη διεύθυνση email.',
                'et' => 'Kasutage kehtivat e-posti aadressi.',
                'es' => 'Utilice una dirección de correo electrónico válida.',
                'fi' => 'KÃ¤ytÃ¤ kelvollista sÃ¤hkÃ¶postiosoitetta.',
                'hr' => 'Koristite valjanu adresu e-pošte.',
                'hu' => 'Használjon érvényes e-mail címet.',
                'it' => 'Usa un indirizzo email valido.',
                'ja' => '有効なメールアドレスを使用してください。',
                'lt' => 'Naudokite galiojantį el. pašto adresą.',
                'lv' => 'Izmantojiet derīgu e-pasta adresi.',
                'nl' => 'Gebruik een geldig e-mailadres.',
                'no' => 'Bruk en gyldig e-postadresse.',
                'pl' => 'Użyj prawidłowego adresu e-mail.',
                'pt' => 'Utilize um endereço de e-mail válido.',
                'ro' => 'Utilizați o adresă de email validă.',
                'ru' => 'Используйте действительный адрес электронной почты.',
                'sk' => 'Použite platnú e-mailovú adresu.',
                'sl' => 'Uporabite veljaven e-poštni naslov.',
                'sr' => 'Koristite ispravnu adresu e-pošte.',
                'sv' => 'Använd en giltig e-postadress.',
                'uk' => 'Використовуйте дійсну адресу електронної пошти.',
                'vi' => 'Sử dụng một địa chỉ email hợp lệ.',
                'zh' => '使用有效的电子邮件地址.'
            ],
            
            'Please confirm that you are human. Try again.' => [
                'en' => 'Please confirm that you are human. Try again.',
                'de' => 'Bitte bestätigen Sie, dass Sie ein Mensch sind. Versuchen Sie es erneut.',
                'es' => 'Por favor, confirma que eres humano. Inténtalo de nuevo.',
                'fr' => 'Veuillez confirmer que vous êtes humain. Essayez à nouveau.',
                'it' => 'Si prega di confermare di essere umano. Riprova.',
                'nl' => 'Bevestig alstublieft dat u een mens bent. Probeer opnieuw.',
                'pl' => 'Proszę potwierdź, że jesteś człowiekiem. Spróbuj ponownie.',
                'pt' => 'Por favor, confirme que é humano. Tente novamente.',
                'ro' => 'Vă rugăm să confirmați că sunteți uman. Încercați din nou.',
                'sv' => 'Bekräfta att du är mänsklig. Försök igen.',
                'bg' => 'Моля, потвърдете, че сте човек. Опитайте отново.',
                'ca' => 'Si us plau, confirmeu que sou humà. Proveu-ho de nou.',
                'cs' => 'Prosím, potvrďte, že jste člověk. Zkuste to znovu.',
                'da' => 'Bekræft venligst, at du er et menneske. Prøv igen.',
                'el' => 'Παρακαλώ επιβεβαιώστε ότι είστε άνθρωπος. Δοκιμάστε ξανά.',
                'et' => 'Palun kinnitage, et olete inimene. Proovige uuesti.',
                'hr' => 'Molimo potvrdite da ste čovjek. Pokušajte ponovno.',
                'hu' => 'Kérjük, erősítse meg, hogy ember vagy. Próbálja újra.',
                'ja' => '人間であることを確認してください。 もう一度やり直してください。',
                'lt' => 'Prašome patvirtinti, kad esate žmogus. Bandykite dar kartą.',
                'lv' => 'Lūdzu, apstipriniet, ka esat cilvēks. Mēģiniet vēlreiz.',
                'no' => 'Vennligst bekreft at du er et menneske. Prøv igjen.',
                'ru' => 'Пожалуйста, подтвердите, что вы человек. Попробуйте еще раз.',
                'sk' => 'Prosím, potvrďte, že ste človek. Skúste to znova.',
                'sl' => 'Prosim, potrdite, da ste človek. Poskusite znova.',
                'sr' => 'Молимо потврдите да сте човек. Покушајте поново.',
                'uk' => 'Будь ласка, підтвердіть, що ви людина. Спробуйте ще раз.',
                'vi' => 'Vui lòng xác nhận rằng bạn là người. Thử lại.',
                'zh' => '请确认您是人类。 再试一次。'
            ],
            
            'Robot check failed, please retry.' => [
                'en' => 'Robot check failed, please retry.',
                'de' => 'Roboterprüfung fehlgeschlagen, bitte versuchen Sie es erneut.',
                'es' => 'La verificación de robot falló, por favor inténtelo de nuevo.',
                'fr' => 'Vérification du robot échouée, veuillez réessayer.',
                'it' => 'Controllo del robot fallito, si prega di riprovare.',
                'nl' => 'Robotcontrole mislukt, probeer het opnieuw.',
                'pl' => 'Weryfikacja robota nie powiodła się, spróbuj ponownie.',
                'pt' => 'Verificação de robô falhou, por favor tente novamente.',
                'ro' => 'Verificarea robotului a eșuat, vă rugăm să încercați din nou.',
                'sv' => 'Robotkontrollen misslyckades, försök igen.',
                'bg' => 'Проверката на робота неуспешна, моля опитайте отново.',
                'ca' => 'La comprovació del robot ha fallat, si us plau torneu a intentar-ho.',
                'cs' => 'Kontrola robota selhala, zkuste to znovu.',
                'da' => 'Robotkontrollen mislykkedes, prøv venligst igen.',
                'el' => 'Αποτυχία ελέγχου ρομπότ, παρακαλώ δοκιμάστε ξανά.',
                'et' => 'Roboti kontroll nurjus, palun proovige uuesti.',
                'hr' => 'Provjera robota nije uspjela, molimo pokušajte ponovno.',
                'hu' => 'A robot ellenőrzése sikertelen, kérjük, próbálja újra.',
                'ja' => 'ロボットのチェックに失敗しました、もう一度やり直してください。',
                'lt' => 'Robotas nepavyko patikrinti, prašome bandyti dar kartą.',
                'lv' => 'Robota pārbaude neizdevās, lūdzu, mēģiniet vēlreiz.',
                'no' => 'Robotkontrollen mislyktes, prøv igjen.',
                'ru' => 'Проверка робота не удалась, пожалуйста, повторите попытку.',
                'sk' => 'Kontrola robota zlyhala, skúste to znovu.',
                'sl' => 'Preverjanje robota ni uspelo, poskusite znova.',
                'sr' => 'Provera robota nije uspela, molimo pokušajte ponovo.',
                'uk' => 'Перевірка робота не вдалася, будь ласка, спробуйте ще раз.',
                'vi' => 'Kiểm tra robot không thành công, vui lòng thử lại.',
                'zh' => '机器人检查失败，请重试。'
            ],
            
            'Confirm the robot check again.' => [
                'en' => 'Confirm the robot check again.',
                'de' => 'Bestätigen Sie die Roboterprüfung erneut.',
                'es' => 'Confirma la verificación del robot de nuevo.',
                'fr' => 'Confirmez la vérification du robot à nouveau.',
                'it' => 'Conferma il controllo del robot di nuovo.',
                'nl' => 'Bevestig de robotcontrole opnieuw.',
                'pl' => 'Potwierdź ponownie weryfikację robota.',
                'pt' => 'Confirme a verificação do robô novamente.',
                'ro' => 'Confirmați verificarea robotului din nou.',
                'sv' => 'Bekräfta robotkontrollen igen.',
                'bg' => 'Потвърдете проверката на робота отново.',
                'ca' => 'Confirmeu la comprovació del robot de nou.',
                'cs' => 'Potvrďte kontrolu robota znovu.',
                'da' => 'Bekræft robotkontrollen igen.',
                'el' => 'Επιβεβαιώστε ξανά τον έλεγχο του ρομπότ.',
                'et' => 'Kinnitage roboti kontroll uuesti.',
                'hr' => 'Ponovno potvrdite provjeru robota.',
                'hu' => 'Erősítse meg újra a robot ellenőrzését.',
                'ja' => 'もう一度ロボットチェックを確認してください。',
                'lt' => 'Pakartotinai patvirtinkite robotų patikrinimą.',
                'lv' => 'Vēlreiz apstipriniet robotu pārbaudi.',
                'no' => 'Bekreft robotkontrollen på nytt.',
                'ru' => 'Подтвердите проверку робота снова.',
                'sk' => 'Znova potvrďte kontrolu robota.',
                'sl' => 'Ponovno potrdite preverjanje robota.',
                'sr' => 'Ponovo potvrdite proveru robota.',
                'uk' => 'Підтвердіть перевірку робота знову.',
                'vi' => 'Xác nhận lại việc kiểm tra robot.',
                'zh' => '再次确认机器人检查。'
            ],
            
            'Your access has been blocked.' => [
                'en' => 'Your access has been blocked.',
                'de' => 'Ihr Zugang wurde blockiert.',
                'es' => 'Tu acceso ha sido bloqueado.',
                'fr' => 'Votre accès a été bloqué.',
                'it' => 'Il tuo accesso è stato bloccato.',
                'nl' => 'Uw toegang is geblokkeerd.',
                'pl' => 'Twój dostęp został zablokowany.',
                'pt' => 'Seu acesso foi bloqueado.',
                'ro' => 'Accesul tău a fost blocat.',
                'sv' => 'Din åtkomst har blockerats.',
                'bg' => 'Вашият достъп е блокиран.',
                'ca' => 'El vostre accés ha estat bloquejat.',
                'cs' => 'Váš přístup byl zablokován.',
                'da' => 'Din adgang er blevet blokeret.',
                'el' => 'Ο προσωπικός σας κωδικός πρόσβασης έχει μπλοκαριστεί.',
                'et' => 'Teie juurdepääs on blokeeritud.',
                'hr' => 'Vaš pristup je blokiran.',
                'hu' => 'A hozzáférése blokkolva lett.',
                'ja' => 'アクセスがブロックされました。',
                'lt' => 'Jūsų prieiga užblokuota.',
                'lv' => 'Jums ir bloķēts piekļuve.',
                'no' => 'Din tilgang er blokkert.',
                'ru' => 'Ваш доступ заблокирован.',
                'sk' => 'Váš prístup bol zablokovaný.',
                'sl' => 'Vaš dostop je bil blokiran.',
                'sr' => 'Ваш приступ је блокиран.',
                'uk' => 'Ваш доступ заблоковано.',
                'vi' => 'Quyền truy cập của bạn đã bị chặn.',
                'zh' => '您的访问已被阻止。'
            ],
        ];

        if (array_key_exists($key, $translations)) {
            $translation = $translations[$key];
            if (isset($translation[$preferred_lang_code])) {
                return $translation[$preferred_lang_code];
            } else {
                return $translation['en'];
            }
        } else {
            return $key;
        }

    }

    private function isEmailField($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function onCheckAnswer($code = null)
    {
        $input = Factory::getApplication()->input;
        $secret_key = $this->params->get('secret_key');
        $zencaptcha_solution = $code ?? $input->get('zenc-captcha-solution', '', 'string');

        $formData = $input->post->getArray();

        // Iterate through the form data to find an input of type "email"
        // only if LITE, PRO etc..
        $verify_emails = $this->params->get('verify_emails', 'no');
        $foundEmail="";
        if($verify_emails=='yes'){
            foreach ($formData as $fieldName => $fieldValue) {
                if (is_array($fieldValue)) {
                    foreach ($fieldValue as $subFieldName => $subFieldValue) {
                        if ($this->isEmailField($subFieldValue)) {
                            $foundEmail = $subFieldValue;
                            break 2;
                        }
                    }
                } else {
                    if ($this->isEmailField($fieldValue)) {
                        $foundEmail = $fieldValue;
                        break;
                    }
                }
            }            
        }

        if (empty($secret_key)) {
            throw new \RuntimeException(Text::_('PLG_CAPTCHA_ZENCAPTCHA_ERROR_SECRETKEY_MISSING'));
            return false;
        }

        if (empty($zencaptcha_solution)) {
            throw new \RuntimeException($this->translate_zen('Please confirm that you are human. Try again.'));
            return false;
        }

        return $this->getResponse($secret_key, $zencaptcha_solution, $foundEmail);
    }


    private function getResponse(string $secret_key, string $zencaptcha_solution, string $foundEmail="")
    {
        
        $siteverify_url = 'https://www.zencaptcha.com/captcha/siteverify';

        $data = array(
            "response" => $zencaptcha_solution,
            "secret" => $secret_key
        );
        if (!empty($foundEmail)) {
            $data['email'] = $foundEmail;
        }

        $options = array(
            CURLOPT_URL            => $siteverify_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
        );
        
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return true;
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);

        if (empty($response) || $httpCode != 200) {
            return true;
        }

        $captchaSuccess = json_decode($response, true);

        $success = isset($captchaSuccess['success']) ? $captchaSuccess['success'] : false;
        $message = isset($captchaSuccess['message']) ? $captchaSuccess['message'] : 'invalid_solution';
        $fraudscore = isset($captchaSuccess['fraudscore']) ? $captchaSuccess['fraudscore'] : 20;
        $countrycode = isset($captchaSuccess['countrycode']) ? $captchaSuccess['countrycode'] : 'XX';
        $emailvalid = isset($captchaSuccess['emailvalid']) ? $captchaSuccess['emailvalid'] : 'none';

        if (!$success) {
            throw new \RuntimeException($this->translate_zen('Please confirm that you are human. Try again.'));
            return false;
        }
    
        if($message != "valid"){
            throw new \RuntimeException($this->translate_zen('Please confirm that you are human. Try again.'));
            return false;
        }
    
    
        if (!empty($foundEmail) && ($emailvalid === "invalid_email" || $emailvalid === "disposable_email")) {
            throw new \RuntimeException($this->translate_zen('Use a valid email address.'));
            return false;
        }

        return true;

    }
}
