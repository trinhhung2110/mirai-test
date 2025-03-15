export interface Folders {
  id: number;
  name: string;
  children: FolderChildren[];
};

export interface FolderChildren {
  id: number;
  name: string;
  children: Files[];
};

export interface Files {
  id: number;
  name: string;
  url: string;
  type: string;
  dimension: string;
  size: string;
  photo_by: string;
  selected: boolean;
};
