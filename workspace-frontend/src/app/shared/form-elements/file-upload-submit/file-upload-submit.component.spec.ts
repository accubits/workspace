import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FileUploadSubmitComponent } from './file-upload-submit.component';

describe('FileUploadSubmitComponent', () => {
  let component: FileUploadSubmitComponent;
  let fixture: ComponentFixture<FileUploadSubmitComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FileUploadSubmitComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FileUploadSubmitComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
