@echo off
title Cloudflare Tunnel - BHP Inventaris
echo ========================================================
echo   Menyalakan Cloudflare Tunnel untuk BHP Inventaris
echo   Mengarahkan ke http://127.0.0.1:8000
echo ========================================================
echo.
echo Tunggu beberapa saat sampai link "https://...trycloudflare.com" muncul.
echo Link tersebut bisa dibagikan ke siapa saja untuk akses online!
echo.
"C:\Program Files (x86)\cloudflared\cloudflared.exe" tunnel --url http://127.0.0.1:8000
pause

