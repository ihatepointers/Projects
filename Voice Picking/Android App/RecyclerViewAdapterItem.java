package com.example.speechrec;

import android.content.Context;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.RelativeLayout;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import org.javatuples.Quartet;
import org.javatuples.Quintet;
import org.javatuples.Sextet;

import java.util.ArrayList;

public class RecyclerViewAdapterItem extends RecyclerView.Adapter<RecyclerViewAdapterItem.ViewHolder>{

    private static final String TAG = "RecyclerViewAdapterOrdr";
    private Context mContext;
    private ArrayList<Sextet<Integer, String, String, Integer, String, Integer>> items = new ArrayList<Sextet<Integer, String, String, Integer, String, Integer>>();
    //private int type;

    public RecyclerViewAdapterItem(Context mContext, ArrayList<Sextet<Integer, String, String, Integer, String, Integer>> items) {
        this.mContext = mContext;
        this.items = items;
    }

    class ItemViewHolder extends RecyclerView.ViewHolder {

        ItemViewHolder(@NonNull View itemView) {
            super(itemView);
        }
    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {

        View view;
        if (viewType == 0) { // for call layout
            view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_item, parent, false);
            return new ViewHolder(view);

        } else if (viewType == 1) { // for call layout
            view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_item_green, parent, false);
            return new ViewHolder(view);

        } else { // for email layout
            view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_item_orange, parent, false);
            return new ViewHolder(view);
        }

        //View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_item, parent, false);
        //ViewHolder holder = new ViewHolder(view);
        //return holder;
    }

    @Override
    public void onBindViewHolder(ViewHolder holder, int position) {
        Log.d(TAG, "onBindViewHolder: called.");
        //holder.orderView.setText(position + ". " + orders.get(position).getValue(0).toString() + " | " + orders.get(position).getValue(1).toString() + " | " + orders.get(position).getValue(2).toString() + " | " + orders.get(position).getValue(3).toString());
        holder.number.setText(String.valueOf(position));
        holder.itemLogicalRef.setText(items.get(position).getValue(0).toString());
        holder.itemCode.setText(items.get(position).getValue(1).toString());
        holder.itemName.setText(items.get(position).getValue(2).toString());
        holder.itemAmount.setText(items.get(position).getValue(3).toString());
        holder.itemAmountType.setText(items.get(position).getValue(4).toString());



    }

    @Override
    public int getItemCount() {
        return items.size();
    }

    public int getItemViewType(int position) {
        //int type = (int) items.get(position).get(items.get(position).keySet().toArray()[0]);
        int type = items.get(position).getValue5();

        return type;

    }

    public class ViewHolder extends RecyclerView.ViewHolder{

        RelativeLayout parentlayout;
        TextView number;
        TextView itemLogicalRef;
        TextView itemCode;
        TextView itemName;
        TextView itemAmount;
        TextView itemAmountType;
        public ViewHolder(View itemView){
            super(itemView);
            number = itemView.findViewById(R.id.number);
            itemLogicalRef = itemView.findViewById(R.id.itemLogicalRef);
            itemCode = itemView.findViewById(R.id.itemCode);
            itemName = itemView.findViewById(R.id.itemName);
            itemAmount = itemView.findViewById(R.id.itemAmount);
            itemAmountType = itemView.findViewById(R.id.itemAmountType);

        }

    }

}