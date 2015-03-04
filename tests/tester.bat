@echo off
%CD%\..\vendor\bin\tester.bat -w %CD%\reCAPTCHA -c php-win.ini -s -j 40 -log %CD%\recaptcha.log
rmdir %CD%\tmp /Q /S