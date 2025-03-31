<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exercise;
use League\Csv\Reader;
use Illuminate\Support\Facades\Storage;

class ExerciseController extends Controller
{
    public function importCsv()
{
    $filePath = storage_path('app/exercise.csv');
    
    if (!file_exists($filePath)) {
        \Log::error("Arquivo CSV não encontrado: $filePath");
        return back()->with('error', 'Arquivo exercise.csv não encontrado');
    }

    try {
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);
        
        $records = $csv->getRecords();
        $importedCount = 0;

        Exercise::truncate();

        foreach ($records as $record) {
            Exercise::create([
                'csv_id' => $record['id'] ?? null,
                'diet' => $record['diet'] ?? '',
                'pulse' => (int)($record['pulse'] ?? 0),
                'time' => $record['time'] ?? '',
                'kind' => $record['kind'] ?? 'unknown',
            ]);
            $importedCount++;
        }

        \Log::info("Importados $importedCount registros");
        return redirect('/exercises/stats')->with('success', "$importedCount registros importados com sucesso!");

    } catch (\Exception $e) {
        \Log::error("Erro ao importar CSV: " . $e->getMessage());
        return back()->with('error', 'Erro ao processar arquivo CSV');
    }
}
    
public function stats()
{
    // Debug 1: Verifica se está chegando no método
    \Log::info('Acessando método stats()');
    
    $exercises = Exercise::all();
    
    // Debug 2: Mostra consulta SQL no log
    \Log::debug('SQL: '. Exercise::toSql()); 
    \Log::debug('Dados encontrados: '. $exercises->count());
    
    $stats = $exercises->groupBy('kind')->map(function($items) {
        return [
            'count' => $items->count(),
            'avg_pulse' => $items->avg('pulse')
        ];
    });

    // Debug 3: Mostra estatísticas calculadas
    \Log::debug('Stats calculadas:', $stats->toArray());
    
    return view('exercises.stats', [
        'stats' => $stats,
        'rawData' => $exercises // Envia dados brutos para debug na view
    ]);
}
}
