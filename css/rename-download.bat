@echo off
for %%f in (*.download) do (
    set "filename=%%~nf"
    ren "%%f" "%%~nf"
)