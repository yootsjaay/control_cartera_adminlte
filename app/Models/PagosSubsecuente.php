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
 * @property string $numero_recibo
 * @property Carbon $vigencia_desde
 * @property float $importe
 * @property Carbon $fecha_limite_pago
 * @property string $estado
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
		'vigencia_desde' => 'datetime',
		'importe' => 'float',
		'fecha_limite_pago' => 'datetime'
	];

	protected $fillable = [
		'poliza_id',
		'numero_recibo',
		'vigencia_desde',
		'importe',
		'fecha_limite_pago',
		'estado'
	];

	public function poliza()
	{
		return $this->belongsTo(Poliza::class);
	}
}
