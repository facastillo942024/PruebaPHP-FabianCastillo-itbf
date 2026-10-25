import { useEffect, useState } from 'react';
import { CatalogService } from '../services/api';

/**
 * Carga los catálogos estáticos (ciudades, tipos, acomodaciones y la matriz
 * de compatibilidad) una sola vez al montar el componente.
 */
export function useCatalogs() {
  const [catalogs, setCatalogs] = useState({
    cities: [],
    room_types: [],
    accommodations: [],
    compatibility: [],
  });
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    let active = true;
    CatalogService.get()
      .then((data) => active && setCatalogs(data))
      .catch(() => active && setError('No se pudieron cargar los catálogos.'))
      .finally(() => active && setLoading(false));
    return () => {
      active = false;
    };
  }, []);

  return { catalogs, loading, error };
}
