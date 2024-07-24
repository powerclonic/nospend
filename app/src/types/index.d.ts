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

export interface Dashboard {
  name: string;
  today_expenses: Array<Expense>;
  month_statistics: {
    expenses_quantity: number;
    expenses_total_value: number;
    expenses_total_paid: number;
    expenses_total_unpaid: number;
    expenses_total_not_recurrent: number;
  };
}

export interface SignInData {
  email: string;
  password: string;
}

export interface SignUpData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}
