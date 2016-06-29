@echo off
%CD%\..\vendor\bin\tester.bat %CD%\cases -c php-win.ini -s -j 40 -log %CD%\tester.log --coverage coverage.html --coverage-src %CD%\..\src %*
rmdir %CD%\tmp /Q /S
