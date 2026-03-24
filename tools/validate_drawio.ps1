Get-ChildItem 'c:/Users/XPS/Documents/salon2/docs/diagrammes/*.drawio' | ForEach-Object {
    Write-Host ('=== ' + $_.Name + ' ===')
    try {
        [xml]$x = Get-Content $_.FullName
        Write-Host 'OK'
    } catch {
        Write-Host ('ERROR: ' + $_.Exception.Message)
    }
}
