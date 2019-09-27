using System;
using System.Collections.Generic;
using System.ServiceModel;

namespace GettingStartedLib
{
    
    [ServiceContract(Namespace = "http://tempuri.org")]
    public interface ICalculator
    {


        [OperationContract]
        string MirrorText(string text);

        [OperationContract]
        List<string> ProcessText(string text);

        [OperationContract]
        List<string> predictSpeech(byte[] soundData);


    }

    
}
