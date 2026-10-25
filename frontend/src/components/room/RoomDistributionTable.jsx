/**
 * Tabla de distribución de habitaciones asignadas a un hotel.
 */
export default function RoomDistributionTable({ rooms = [] }) {
  if (rooms.length === 0) {
    return (
      <p className="py-3 text-sm text-slate-500">
        Este hotel aún no tiene habitaciones asignadas.
      </p>
    );
  }

  return (
    <table className="w-full text-left text-sm">
      <thead>
        <tr className="border-b text-slate-500">
          <th className="py-2 font-medium">Cantidad</th>
          <th className="py-2 font-medium">Tipo</th>
          <th className="py-2 font-medium">Acomodación</th>
        </tr>
      </thead>
      <tbody>
        {rooms.map((room) => (
          <tr key={room.id} className="border-b last:border-0">
            <td className="py-2 font-semibold">{room.quantity}</td>
            <td className="py-2">{room.room_type}</td>
            <td className="py-2">{room.accommodation}</td>
          </tr>
        ))}
      </tbody>
    </table>
  );
}
