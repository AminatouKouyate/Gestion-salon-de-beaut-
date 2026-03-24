Get-ChildItem 'c:/Users/XPS/Documents/salon2/docs/diagrammes/*.drawio' | ForEach-Object {
    $file = $_.FullName
    $content = Get-Content $file -Raw
    # Remove XML comments
    $cleaned = [regex]::Replace($content, '<!--.*?-->\s*\r?\n?', '', 'Singleline')
    # Remove resulting blank lines (multiple newlines)
    $cleaned = [regex]::Replace($cleaned, '(\r?\n){3,}', "`r`n`r`n")
    Set-Content -Path $file -Value $cleaned -NoNewline
    Write-Host ('Cleaned: ' + $_.Name)
}
