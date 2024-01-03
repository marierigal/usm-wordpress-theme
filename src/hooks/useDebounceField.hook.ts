import { useCallback, useEffect, useState } from 'react';

import { useDebounceValue } from './useDebounceValue.hook';

export const useDebounceField = <T>(
  value: T,
  onChange: (value: T) => void,
  delay = 500
): [value: T, setValue: (value: T) => void] => {
  const [tmpValue, setTmpValue] = useState(value);
  const debounceValue = useDebounceValue(tmpValue, delay);
  const onChangeCached = useCallback(onChange, []);

  useEffect(() => {
    if (debounceValue !== value) {
      onChangeCached(debounceValue);
    }
  }, [debounceValue, value, onChangeCached]);

  return [tmpValue, setTmpValue];
};
