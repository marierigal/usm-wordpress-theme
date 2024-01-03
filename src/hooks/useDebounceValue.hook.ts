import { useEffect, useState } from 'react';

export const useDebounceValue = <T>(rawValue: T, delay = 500): T => {
  const [value, setValue] = useState<T>(rawValue);

  let timeoutId: NodeJS.Timeout;

  useEffect(() => {
    if (timeoutId) {
      clearTimeout(timeoutId);
    }

    timeoutId = setTimeout(() => setValue(rawValue), delay);

    return () => clearTimeout(timeoutId);
  }, [rawValue, delay]);

  return value;
};
