@echo off
@rem win_shellがこけるのでバッチに分ける
control intl.cpl,,/f:"%~dp0intl.xml"
exit /b 0
