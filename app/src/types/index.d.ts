export interface Expense {
  id: number;
  name: string;
  value: number;
  due_date: string;
  status: "NOT_PAID" | "PAID" | "EXPIRED";
  payment_method?: string;
  payment_source?: string;
  created_at: string;
  recurrent: Boolean;
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
