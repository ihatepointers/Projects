package com.example.speechrec;

import android.content.Context;
import android.text.TextUtils;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.RelativeLayout;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.LinkedHashMap;
import java.util.List;

public class RecyclerViewAdapter extends RecyclerView.Adapter<RecyclerViewAdapter.ViewHolder>{

    private static final String TAG = "RecyclerViewAdapter";
    private HashMap<String, Integer> messages = new HashMap<String, Integer>();
    private Context mContext;
    List<HashMap> messageList;

    public RecyclerViewAdapter(Context mContext, List<HashMap> messageList) {
        this.mContext = mContext;
        this.messageList = messageList;
    }

    class MessageViewHolder extends RecyclerView.ViewHolder {

        private TextView txtName;
        private TextView txtAddress;

        MessageViewHolder(@NonNull View itemView) {
            super(itemView);
        }
    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {



        View view;
        if (viewType == 0) { // for call layout
            view = LayoutInflater.from(parent.getContext()).inflate(R.layout.list_item_incoming, parent, false);
            return new ViewHolder(view);

        } else { // for email layout
            view = LayoutInflater.from(parent.getContext()).inflate(R.layout.list_item_outgoing, parent, false);
            return new ViewHolder(view);
        }


        //View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.list_item_incoming, parent, false);
        //ViewHolder holder = new ViewHolder(view);
        //return holder;

    }

    @Override
    public void onBindViewHolder(ViewHolder holder, int position) {
        Log.d(TAG, "onBindViewHolder: called.");

        holder.message.setText((CharSequence) messageList.get(position).keySet().toArray()[0]);
    }


    @Override
    public int getItemViewType(int position) {
        int type = (int) messageList.get(position).get(messageList.get(position).keySet().toArray()[0]);
        if(type == 0){
            return 0;
        }else{
            return 1;
        }
    }


    @Override
    public int getItemCount() {
        return messageList.size();
    }

    public class ViewHolder extends RecyclerView.ViewHolder{
        TextView message;
        RelativeLayout parentLayout;
        public ViewHolder(View itemView){
            super(itemView);
            message = itemView.findViewById(R.id.message);
        }


    }

}
