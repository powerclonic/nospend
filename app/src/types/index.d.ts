export interface Expense {
  id: number;
  name: string;
  value: number;
  due_date: string;
  status: "AWAITING_PAYMENT" | "PAID" | "EXPIRED";
  payment_method?: string;
  payment_source?: string;
  created_at: string;
  recurrent: Boolean;
  auto_pay: Boolean;
  category?: string;
}

export interface CategoryStatistic {
  category: string,
  total_value: number
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
  expenses_by_category: Array<CategoryStatistic>
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