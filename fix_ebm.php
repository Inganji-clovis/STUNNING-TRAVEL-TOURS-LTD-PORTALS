<?php
$ebms = \App\Models\Ebm::all();
foreach($ebms as $m) {
    if(str_starts_with($m->file_path, 'private/')) {
        $n = str_replace('private/', '', $m->file_path);
        if(\Storage::disk('local')->exists($m->file_path)) {
            \Storage::disk('public')->put($n, \Storage::disk('local')->get($m->file_path));
            \Storage::disk('local')->delete($m->file_path);
        }
        $m->update(['file_path' => $n]);
    }
}
echo "Migration successful.\n";
