'use client'
import React, { useState } from 'react';
import { useRouter } from 'next/navigation'
const Page = () => {
  const [selectedFile, setSelectedFile] = useState(null);
  const [uploadStatus, setUploadStatus] = useState('');
  const router = useRouter()
  const handleFileChange = (event) => {
    setSelectedFile(event.target.files[0]);
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    if (!selectedFile) {
      alert('Please select a file');
      return;
    }

    const formData = new FormData();
    formData.append('file', selectedFile);

    try {
   

      const response = await fetch('http://localhost:8000/api/upload/data', {
        headers: {
          'Accept': 'application/json',
        },
        method: 'POST',
        body: formData,
      });
      let data = await response.json()
      console.log(data);
      
      localStorage.setItem('matchData',JSON.stringify(data.matchData))
      localStorage.setItem('excelHeader',JSON.stringify(data.excelHeader))
      localStorage.setItem('tableHeader',JSON.stringify(data.tableHeader))
      setUploadStatus('File uploaded successfully!');
      router.push('/join', { scroll: false })
      
    } catch (error) {
      console.error('Error uploading file:', error);
      setUploadStatus('Failed to upload file');
    }
  };

  return (
    <div className='my-5 container mx-auto'>
      <h2>Upload your Excel Data</h2>
      <form onSubmit={handleSubmit} className='my-3'>
      <label className="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>
      <input onChange={handleFileChange}  className="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file_input" type="file" />
        
      <div className='my-2'>
      <button type="submit" className='border border-gray-400 px-3 py-2 shadow-sm'>Upload</button>
      </div>
      </form>
      {uploadStatus && <p>{uploadStatus}</p>}
    </div>
  );
};

export default Page;
