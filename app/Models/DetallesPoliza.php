<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DetallesPoliza
 * 
 * @property int $id
 * @property int $id_poliza
 * @property int $id_subtipo_seguro
 * @property string|null $otros_detalles
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Poliza $poliza
 * @property SubtiposSeguro $subtipos_seguro
 *
 * @package App\Models
 */
class DetallesPoliza extends Model
{
	protected $table = 'detalles_polizas';

	protected $casts = [
		'id_poliza' => 'int',
		'id_subtipo_seguro' => 'int'
	];

	protected $fillable = [
		'id_poliza',
		'id_subtipo_seguro',
		'otros_detalles'
	];

	public function poliza()
	{
		return $this->belongsTo(Poliza::class, 'id_poliza');
	}

	public function subtipos_seguro()
	{
		return $this->belongsTo(SubtiposSeguro::class, 'id_subtipo_seguro');
	}
}
