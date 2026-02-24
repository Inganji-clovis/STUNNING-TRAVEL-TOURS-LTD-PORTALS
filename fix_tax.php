<?php
$taxes = \App\Models\TaxCompliance::all();
foreach($taxes as $t) {
    if($t->declaration_file && str_starts_with($t->declaration_file, 'private/')) {
        $n = str_replace('private/', '', $t->declaration_file);
        if(\Storage::disk('local')->exists($t->declaration_file)) {
            \Storage::disk('public')->put($n, \Storage::disk('local')->get($t->declaration_file));
            \Storage::disk('local')->delete($t->declaration_file);
        }
        $t->update(['declaration_file' => $n]);
    }
    
    if($t->receipt_file && str_starts_with($t->receipt_file, 'private/')) {
        $n = str_replace('private/', '', $t->receipt_file);
        if(\Storage::disk('local')->exists($t->receipt_file)) {
            \Storage::disk('public')->put($n, \Storage::disk('local')->get($t->receipt_file));
            \Storage::disk('local')->delete($t->receipt_file);
        }
        $t->update(['receipt_file' => $n]);
    }
}
echo "Tax migration successful.\n";
