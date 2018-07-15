export const ACCOUNT_PATH_SEPARATOR = ' / ';

export const AccountTitleType = {
  ASSET: 'ASSET',
  LIABILITY: 'LIABILITY',
  NET_ASSET: 'NET_ASSET',
  REVENUE: 'REVENUE',
  EXPENSE: 'EXPENSE',
  OTHER: 'OTHER',
};

export const FinancialStatementType = {
  BALANCE_SHEET: 'BALANCE_SHEET',
  PROFIT_AND_LOSS_STATEMENT: 'PROFIT_AND_LOSS_STATEMENT',
};

export const AccountTitleTypeDesc = {
  [AccountTitleType.ASSET]: {
    order: 10,
    statements: {
      [FinancialStatementType.BALANCE_SHEET]: true,
    },
  },
  [AccountTitleType.LIABILITY]: {
    order: 20,
    statements: {
      [FinancialStatementType.BALANCE_SHEET]: true,
    },
  },
  [AccountTitleType.NET_ASSET]: {
    order: 30,
    statements: {
      [FinancialStatementType.BALANCE_SHEET]: true,
    },
  },
  [AccountTitleType.REVENUE]: {
    order: 40,
    statements: {
      [FinancialStatementType.PROFIT_AND_LOSS_STATEMENT]: true,
    },
  },
  [AccountTitleType.EXPENSE]: {
    order: 50,
    statements: {
      [FinancialStatementType.PROFIT_AND_LOSS_STATEMENT]: true,
    },
  },
  [AccountTitleType.OTHER]: {
    order: 60,
    statements: {},
  },
};
