import RoomDistributionTable from '../room/RoomDistributionTable';

/**
 * Tarjeta de un hotel con su resumen, distribución de habitaciones y acciones.
 */
export default function HotelCard({ hotel, onAssign, onDelete }) {
  return (
    <article className="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
      <div className="flex items-start justify-between gap-4">
        <div>
          <h3 className="text-lg font-semibold text-slate-800">{hotel.name}</h3>
          <p className="text-sm text-slate-500">{hotel.address}</p>
          <p className="mt-1 text-xs text-slate-400">
            {hotel.city?.name} · NIT {hotel.nit}
          </p>
        </div>
        <div className="text-right text-sm">
          <p className="font-semibold text-decameron">{hotel.assigned_rooms ?? 0}/{hotel.max_rooms}</p>
          <p className="text-xs text-slate-400">asignadas</p>
        </div>
      </div>

      <div className="mt-4">
        <RoomDistributionTable rooms={hotel.rooms} />
      </div>

      <div className="mt-4 flex gap-2">
        <button
          type="button"
          onClick={() => onAssign(hotel)}
          className="rounded-md bg-decameron px-3 py-1.5 text-sm font-medium text-white hover:bg-decameron-dark"
        >
          Asignar habitaciones
        </button>
        <button
          type="button"
          onClick={() => onDelete(hotel)}
          className="rounded-md border border-red-200 px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50"
        >
          Eliminar
        </button>
      </div>
    </article>
  );
}
