@echo off
%CD%\..\vendor\bin\tester.bat %CD%\reCAPTCHA -c php-win.ini -s -j 40 -log %CD%\recaptcha.log --coverage coverage.html --coverage-src %CD%\..\src %*
rmdir %CD%\tmp /Q /S
