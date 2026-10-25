/**
 * Utilidades de validación de formularios en el cliente.
 * La validación definitiva siempre ocurre en el backend; esto solo mejora la UX.
 */

export function validateHotel(values) {
  const errors = {};
  if (!values.name?.trim()) errors.name = 'El nombre es obligatorio.';
  if (!values.address?.trim()) errors.address = 'La dirección es obligatoria.';
  if (!values.city_id) errors.city_id = 'Seleccione una ciudad.';
  if (!values.nit?.trim()) errors.nit = 'El NIT es obligatorio.';
  if (!values.max_rooms || Number(values.max_rooms) < 1) {
    errors.max_rooms = 'El número de habitaciones debe ser mayor que cero.';
  }
  return errors;
}

/**
 * Devuelve las acomodaciones permitidas para un tipo de habitación, según la
 * matriz de compatibilidad recibida del backend.
 */
export function allowedAccommodations(compatibility, roomTypeId, accommodations) {
  if (!roomTypeId) return [];
  const allowedIds = compatibility
    .filter((c) => String(c.room_type_id) === String(roomTypeId))
    .map((c) => c.accommodation_id);
  return accommodations.filter((a) => allowedIds.includes(a.id));
}

/**
 * Extrae un mensaje legible desde un error de axios (incluye los 422 del backend).
 */
export function extractApiError(error) {
  const data = error?.response?.data;
  if (data?.errors) {
    return Object.values(data.errors).flat().join(' ');
  }
  return data?.message || 'Ocurrió un error inesperado. Intente nuevamente.';
}
