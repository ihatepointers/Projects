using System;
using System.Collections.Generic;
using System.ServiceModel;

namespace GettingStartedLib
{
    
    [ServiceContract(Namespace = "http://tempuri.org")]
    public interface ICalculator
    {

        [OperationContract]
        double Add(double n1, double n2);

        [OperationContract]
        double Subtract(double n1, double n2);

        [OperationContract]
        double Multiply(double n1, double n2);

        [OperationContract]
        double Divide(double n1, double n2);

        [OperationContract]
        string GetText();

        [OperationContract]
        string MirrorText(string text);

        [OperationContract]
        List<string> ProcessText(string text);

        [OperationContract]
        List<string> predictSpeech(byte[] soundData);


    }

    
}