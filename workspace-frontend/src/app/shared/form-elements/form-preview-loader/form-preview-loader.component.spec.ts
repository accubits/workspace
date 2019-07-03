import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormPreviewLoaderComponent } from './form-preview-loader.component';

describe('FormPreviewLoaderComponent', () => {
  let component: FormPreviewLoaderComponent;
  let fixture: ComponentFixture<FormPreviewLoaderComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormPreviewLoaderComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormPreviewLoaderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
