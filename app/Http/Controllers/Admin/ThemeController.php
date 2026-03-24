<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

/**
 * Contrôleur de gestion du thème global pour le panneau d'administration.
 *
 * Permet à l'administrateur de modifier le thème de couleur et le mode sombre
 * de l'application. Les modifications sont appliquées globalement à toutes les vues.
 *
 * @package App\Http\Controllers\Admin
 */
class ThemeController extends Controller
{
    /**
     * Met à jour les paramètres du thème global de l'application.
     *
     * Valide et enregistre le thème de couleur et/ou le mode sombre.
     * Invalide le cache des paramètres après la mise à jour.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'color_theme' => 'sometimes|string|in:rose-gold,ocean-blue,emerald,royal-purple,sunset,teal-coral,cherry,slate',
            'dark_mode' => 'sometimes|boolean',
        ]);

        $settings = Setting::first() ?? new Setting();
        $settings->fill($validated);
        $settings->save();

        Setting::clearCache();

        return response()->json(['success' => true]);
    }
}
