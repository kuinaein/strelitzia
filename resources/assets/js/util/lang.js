export function csvContains(target, searchValue, delimiter) {
  return -1 !== (delimiter + target + delimiter)
    .indexOf(delimiter + searchValue + delimiter);
}
