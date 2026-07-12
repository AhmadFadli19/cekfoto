<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faskes extends Model
{
    protected $fillable = [
        'nama', 'tipe', 'alamat', 'province', 'city',
        'latitude', 'longitude', 'telepon',
        'has_rhogam', 'has_darah_rh_negatif', 'has_transfusi_thalasemia',
        'has_elektroforesis', 'last_stock_update',
    ];
    protected function casts(): array {
        return [
            'has_rhogam' => 'boolean', 'has_darah_rh_negatif' => 'boolean',
            'has_transfusi_thalasemia' => 'boolean', 'has_elektroforesis' => 'boolean',
            'last_stock_update' => 'datetime',
        ];
    }
    public function stockUpdates(): HasMany { return $this->hasMany(FaskesStockUpdate::class); }
}
