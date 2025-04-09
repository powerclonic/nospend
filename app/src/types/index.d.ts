export interface Expense {
  id: number;
  name: string;
  value: number;
  due_date: string;
  status: "AWAITING_PAYMENT" | "PAID" | "EXPIRED";
  payment_method?: string;
  payment_source?: string;
  created_at: string;
  recurrent: boolean;
  repeat_for: number;
  auto_pay: boolean;
  category?: string;
}

export interface ExpenseInput extends Omit<Expense, "id" | "status" | "created_at"> {
  name: string;
  value: number;
  payment_method?: string;
  payment_source?: string;
  due_date: string;
  category?: string;
  recurrent: boolean;
  repeat_until?: string;
  auto_pay: boolean;
}

export interface NamedStatistic {
  [key: string]: string; // Allow dynamic keys
  total_value: number; // Explicitly include total_value
}

export interface Dashboard {
  name: string;
  today_expenses: Array<Expense>;
  month_statistics: {
    expenses_quantity: number;
    expenses_total_value: number;
    expenses_total_paid: number;
    expenses_total_unpaid: number;
    expenses_total_not_recurrent: number;
    expenses_total_recurrent: number;
  };
  expenses_by_category: Array<NamedStatistic<'category'>>,
  expenses_by_payment_source: Array<NamedStatistic<'payment_source'>>,
  expenses_by_payment_method: Array<NamedStatistic<'payment_method'>>,
}


export interface SignInData {
  email: string;
  password: string;
  remember: boolean;
}

export interface SignUpData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

export type MessageType = "error" | "success" | "warning" | "info";

export interface Hints {
  category: Array<string>;
  payment_method: Array<string>;
  payment_source: Array<string>;
}

