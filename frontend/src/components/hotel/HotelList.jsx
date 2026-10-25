import HotelCard from './HotelCard';

/**
 * Cuadrícula responsiva de hoteles. Pensada para portátiles de 13"-15",
 * con una sola columna en pantallas estrechas y dos en pantallas anchas.
 */
export default function HotelList({ hotels, loading, onAssign, onDelete }) {
  if (loading) {
    return <p className="py-10 text-center text-slate-500">Cargando hoteles…</p>;
  }

  if (hotels.length === 0) {
    return (
      <div className="rounded-lg border border-dashed border-slate-300 bg-white py-10 text-center text-slate-500">
        Aún no hay hoteles registrados. Cree el primero con el formulario.
      </div>
    );
  }

  return (
    <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
      {hotels.map((hotel) => (
        <HotelCard key={hotel.id} hotel={hotel} onAssign={onAssign} onDelete={onDelete} />
      ))}
    </div>
  );
}
