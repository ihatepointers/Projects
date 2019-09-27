package com.example.speechrec;

import android.content.Context;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.RelativeLayout;
import android.widget.TextView;

import androidx.recyclerview.widget.RecyclerView;

import org.javatuples.Quartet;

import java.util.ArrayList;

public class RecyclerViewAdapterOrder extends RecyclerView.Adapter<RecyclerViewAdapterOrder.ViewHolder>{

    private static final String TAG = "RecyclerViewAdapterOrdr";
    private Context mContext;
    private ArrayList<Quartet<String, String, String, Integer>> orders = new ArrayList<Quartet<String, String, String, Integer>>();

    public RecyclerViewAdapterOrder(Context mContext, ArrayList<Quartet<String, String, String, Integer>> orders) {
        this.mContext = mContext;
        this.orders = orders;
    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.order_item, parent, false);
        ViewHolder holder = new ViewHolder(view);
        return holder;
    }

    @Override
    public void onBindViewHolder(ViewHolder holder, int position) {
        Log.d(TAG, "onBindViewHolder: called.");
        //holder.orderView.setText(position + ". " + orders.get(position).getValue(0).toString() + " | " + orders.get(position).getValue(1).toString() + " | " + orders.get(position).getValue(2).toString() + " | " + orders.get(position).getValue(3).toString());
        holder.number.setText(String.valueOf(position));
        holder.orderNo.setText(orders.get(position).getValue(0).toString());
        holder.orderCode.setText(orders.get(position).getValue(2).toString());
        holder.orderName.setText(orders.get(position).getValue(1).toString());
        holder.orderAmount.setText(orders.get(position).getValue(3).toString());



    }

    @Override
    public int getItemCount() {
        return orders.size();
    }

    public class ViewHolder extends RecyclerView.ViewHolder{

        RelativeLayout parentlayout;
        TextView number;
        TextView orderNo;
        TextView orderCode;
        TextView orderName;
        TextView orderAmount;
        public ViewHolder(View itemView){
            super(itemView);
            number = itemView.findViewById(R.id.number);
            orderNo = itemView.findViewById(R.id.orderNo);
            orderCode = itemView.findViewById(R.id.orderCode);
            orderName = itemView.findViewById(R.id.orderName);
            orderAmount = itemView.findViewById(R.id.orderAmount);

        }

    }

}
