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
    isDebitSide: true,
    statements: {
      [FinancialStatementType.BALANCE_SHEET]: true,
    },
  },
  [AccountTitleType.LIABILITY]: {
    order: 20,
    isDebitSide: false,
    statements: {
      [FinancialStatementType.BALANCE_SHEET]: true,
    },
  },
  [AccountTitleType.NET_ASSET]: {
    order: 30,
    isDebitSide: false,
    statements: {
      [FinancialStatementType.BALANCE_SHEET]: true,
    },
  },
  [AccountTitleType.REVENUE]: {
    order: 40,
    isDebitSide: false,
    statements: {
      [FinancialStatementType.PROFIT_AND_LOSS_STATEMENT]: true,
    },
  },
  [AccountTitleType.EXPENSE]: {
    order: 50,
    isDebitSide: true,
    statements: {
      [FinancialStatementType.PROFIT_AND_LOSS_STATEMENT]: true,
    },
  },
  [AccountTitleType.OTHER]: {
    isDebitSide: false,
    order: 60,
    statements: {},
  },
};
