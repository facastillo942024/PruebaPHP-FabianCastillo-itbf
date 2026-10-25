import { useCallback, useEffect, useState } from 'react';
import { HotelService } from '../services/api';

/**
 * Encapsula el estado y las operaciones sobre la colección de hoteles.
 */
export function useHotels() {
  const [hotels, setHotels] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  const refresh = useCallback(() => {
    setLoading(true);
    return HotelService.list()
      .then(setHotels)
      .catch(() => setError('No se pudieron cargar los hoteles.'))
      .finally(() => setLoading(false));
  }, []);

  useEffect(() => {
    refresh();
  }, [refresh]);

  return { hotels, loading, error, refresh };
}
