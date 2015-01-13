@echo off
%CD%\..\vendor\bin\tester.bat -w %CD%\reCAPTCHA -s -j 40 -log %CD%\recaptcha.log
rmdir %CD%\tmp /Q /S