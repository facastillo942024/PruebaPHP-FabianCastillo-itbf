import { useMemo, useState } from 'react';
import { HotelService } from '../../services/api';
import { allowedAccommodations, extractApiError } from '../../utils/validators';
import Alert from '../common/Alert';

/**
 * Formulario para asignar habitaciones a un hotel. El selector de acomodación
 * se filtra dinámicamente según la matriz de compatibilidad, de modo que el
 * usuario solo ve combinaciones válidas (aunque el backend revalida igual).
 */
export default function RoomAssignmentForm({ hotel, catalogs, onAssigned }) {
  const [form, setForm] = useState({ room_type_id: '', accommodation_id: '', quantity: '' });
  const [error, setError] = useState(null);
  const [saving, setSaving] = useState(false);

  const available = hotel.available_rooms ?? hotel.max_rooms;

  const accommodationOptions = useMemo(
    () => allowedAccommodations(catalogs.compatibility, form.room_type_id, catalogs.accommodations),
    [catalogs, form.room_type_id],
  );

  const handleType = (e) =>
    setForm({ ...form, room_type_id: e.target.value, accommodation_id: '' });

  const submit = async (e) => {
    e.preventDefault();
    setError(null);
    setSaving(true);
    try {
      await HotelService.assignRoom(hotel.id, {
        room_type_id: Number(form.room_type_id),
        accommodation_id: Number(form.accommodation_id),
        quantity: Number(form.quantity),
      });
      setForm({ room_type_id: '', accommodation_id: '', quantity: '' });
      onAssigned?.();
    } catch (err) {
      setError(extractApiError(err));
    } finally {
      setSaving(false);
    }
  };

  return (
    <form onSubmit={submit} className="space-y-4">
      <Alert type="error" onClose={() => setError(null)}>{error}</Alert>

      <p className="text-sm text-slate-500">
        Disponibles: <span className="font-semibold">{available}</span> de {hotel.max_rooms}
      </p>

      <div>
        <label className="mb-1 block text-sm font-medium" htmlFor="room_type_id">
          Tipo de habitación
        </label>
        <select
          id="room_type_id"
          value={form.room_type_id}
          onChange={handleType}
          required
          className="w-full rounded-md border border-slate-300 px-3 py-2"
        >
          <option value="">Seleccione…</option>
          {catalogs.room_types.map((t) => (
            <option key={t.id} value={t.id}>{t.name}</option>
          ))}
        </select>
      </div>

      <div>
        <label className="mb-1 block text-sm font-medium" htmlFor="accommodation_id">
          Acomodación
        </label>
        <select
          id="accommodation_id"
          value={form.accommodation_id}
          onChange={(e) => setForm({ ...form, accommodation_id: e.target.value })}
          required
          disabled={!form.room_type_id}
          className="w-full rounded-md border border-slate-300 px-3 py-2 disabled:bg-slate-100"
        >
          <option value="">
            {form.room_type_id ? 'Seleccione…' : 'Elija primero un tipo'}
          </option>
          {accommodationOptions.map((a) => (
            <option key={a.id} value={a.id}>{a.name}</option>
          ))}
        </select>
      </div>

      <div>
        <label className="mb-1 block text-sm font-medium" htmlFor="quantity">
          Cantidad
        </label>
        <input
          id="quantity"
          type="number"
          min="1"
          value={form.quantity}
          onChange={(e) => setForm({ ...form, quantity: e.target.value })}
          required
          className="w-full rounded-md border border-slate-300 px-3 py-2"
        />
      </div>

      <button
        type="submit"
        disabled={saving}
        className="w-full rounded-md bg-decameron px-4 py-2 font-medium text-white hover:bg-decameron-dark disabled:opacity-60"
      >
        {saving ? 'Guardando…' : 'Asignar habitaciones'}
      </button>
    </form>
  );
}
