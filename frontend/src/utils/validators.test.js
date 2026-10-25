import { describe, it, expect } from 'vitest';
import { validateHotel, allowedAccommodations } from './validators';

describe('validateHotel', () => {
  it('reporta errores cuando faltan campos obligatorios', () => {
    const errors = validateHotel({});
    expect(errors).toHaveProperty('name');
    expect(errors).toHaveProperty('nit');
    expect(errors).toHaveProperty('max_rooms');
  });

  it('no reporta errores con datos válidos', () => {
    const errors = validateHotel({
      name: 'DECAMERON CARTAGENA',
      address: 'CALLE 23',
      city_id: 1,
      nit: '12345678-9',
      max_rooms: 42,
    });
    expect(Object.keys(errors)).toHaveLength(0);
  });
});

describe('allowedAccommodations', () => {
  const accommodations = [
    { id: 1, name: 'SENCILLA' },
    { id: 2, name: 'DOBLE' },
    { id: 3, name: 'TRIPLE' },
  ];
  const compatibility = [
    { room_type_id: 1, accommodation_id: 1 },
    { room_type_id: 1, accommodation_id: 2 },
  ];

  it('devuelve solo las acomodaciones compatibles con el tipo', () => {
    const result = allowedAccommodations(compatibility, 1, accommodations);
    expect(result.map((a) => a.name)).toEqual(['SENCILLA', 'DOBLE']);
  });

  it('devuelve vacío cuando no hay tipo seleccionado', () => {
    expect(allowedAccommodations(compatibility, '', accommodations)).toEqual([]);
  });
});
