<?php
class CAPTCHA
{
	public
		$type = 0, #None (0), Image (1), reCAPTCHA (2), ASIRRA (3)
		$key = '';

	#Wydrukuj kod HTML
	function __toString()
	{
		switch($this->type)
		{
			case 1:
			$txt = '<img src="code.php" alt="test" style="border: 1px solid gray" />';
			$txt.= '<input name="code" />';
			break;

			case 2:
			if(file_exists('./plugins/recaptcha/recaptchalib.php'))
			{
				require './plugins/recaptcha/recaptchalib.php';
			}
			else
			{
				throw new Exception('CAPTCHA plugin not found!');
			}
			$txt = recaptcha_get_html($this->key)
			break;

			case 3:
			
			break;

			default:
			$txt = '';
		}
		return $txt;
	}
}