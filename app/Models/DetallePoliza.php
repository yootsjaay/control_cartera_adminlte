<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DetallePoliza
 * 
 * @property int $id
 * @property int $id_poliza
 * @property int $id_tipo_seguro
 * @property string $subtipo_seguro
 * @property string|null $otros_detalles
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Poliza $poliza
 * @property TipoSeguro $tipo_seguro
 *
 * @package App\Models
 */
class DetallePoliza extends Model
{
	protected $table = 'detalle_poliza';

	protected $casts = [
		'id_poliza' => 'int',
		'id_tipo_seguro' => 'int'
	];

	protected $fillable = [
		'id_poliza',
		'id_tipo_seguro',
		'subtipo_seguro',
		'otros_detalles'
	];

	public function poliza()
	{
		return $this->belongsTo(Poliza::class, 'id_poliza');
	}

	public function tipo_seguro()
	{
		return $this->belongsTo(TipoSeguro::class, 'id_tipo_seguro');
	}
}
