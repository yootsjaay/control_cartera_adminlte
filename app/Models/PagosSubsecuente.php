<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PagosSubsecuente
 * 
 * @property int $id
 * @property int $poliza_id
 * @property Carbon $vigencia_inicio
 * @property Carbon $vigencia_fin
 * @property float $importe
 * @property Carbon $fecha_vencimiento
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Poliza $poliza
 *
 * @package App\Models
 */
class PagosSubsecuente extends Model
{
	protected $table = 'pagos_subsecuentes';

	protected $casts = [
		'poliza_id' => 'int',
		'vigencia_inicio' => 'datetime',
		'vigencia_fin' => 'datetime',
		'importe' => 'float',
		'fecha_vencimiento' => 'datetime'
	];

	protected $fillable = [
		'poliza_id',
		'vigencia_inicio',
		'vigencia_fin',
		'importe',
		'fecha_vencimiento'
	];

	public function poliza()
	{
		return $this->belongsTo(Poliza::class);
	}
}
