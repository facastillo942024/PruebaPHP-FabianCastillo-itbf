import { useState } from 'react';
import { HotelService } from '../../services/api';
import { extractApiError, validateHotel } from '../../utils/validators';
import Alert from '../common/Alert';

/**
 * Formulario de registro de un hotel con sus datos tributarios básicos.
 */
export default function HotelForm({ cities, onCreated }) {
  const empty = { name: '', address: '', city_id: '', nit: '', max_rooms: '' };
  const [form, setForm] = useState(empty);
  const [errors, setErrors] = useState({});
  const [apiError, setApiError] = useState(null);
  const [saving, setSaving] = useState(false);

  const change = (e) => setForm({ ...form, [e.target.name]: e.target.value });

  const submit = async (e) => {
    e.preventDefault();
    setApiError(null);
    const localErrors = validateHotel(form);
    setErrors(localErrors);
    if (Object.keys(localErrors).length > 0) return;

    setSaving(true);
    try {
      await HotelService.create({
        ...form,
        city_id: Number(form.city_id),
        max_rooms: Number(form.max_rooms),
      });
      setForm(empty);
      onCreated?.();
    } catch (err) {
      setApiError(extractApiError(err));
    } finally {
      setSaving(false);
    }
  };

  const field = (name, label, props = {}) => (
    <div>
      <label htmlFor={name} className="mb-1 block text-sm font-medium">{label}</label>
      <input
        id={name}
        name={name}
        value={form[name]}
        onChange={change}
        className="w-full rounded-md border border-slate-300 px-3 py-2"
        {...props}
      />
      {errors[name] && <p className="mt-1 text-xs text-red-600">{errors[name]}</p>}
    </div>
  );

  return (
    <form onSubmit={submit} className="space-y-4">
      <Alert type="error" onClose={() => setApiError(null)}>{apiError}</Alert>

      {field('name', 'Nombre del hotel', { placeholder: 'DECAMERON CARTAGENA' })}
      {field('address', 'Dirección', { placeholder: 'CALLE 23 58-25' })}

      <div>
        <label htmlFor="city_id" className="mb-1 block text-sm font-medium">Ciudad</label>
        <select
          id="city_id"
          name="city_id"
          value={form.city_id}
          onChange={change}
          className="w-full rounded-md border border-slate-300 px-3 py-2"
        >
          <option value="">Seleccione…</option>
          {cities.map((c) => (
            <option key={c.id} value={c.id}>{c.name}</option>
          ))}
        </select>
        {errors.city_id && <p className="mt-1 text-xs text-red-600">{errors.city_id}</p>}
      </div>

      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
        {field('nit', 'NIT', { placeholder: '12345678-9' })}
        {field('max_rooms', 'N.º máximo de habitaciones', { type: 'number', min: '1' })}
      </div>

      <button
        type="submit"
        disabled={saving}
        className="w-full rounded-md bg-decameron px-4 py-2 font-medium text-white hover:bg-decameron-dark disabled:opacity-60"
      >
        {saving ? 'Guardando…' : 'Registrar hotel'}
      </button>
    </form>
  );
}
