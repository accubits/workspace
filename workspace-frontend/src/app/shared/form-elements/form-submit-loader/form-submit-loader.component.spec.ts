import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormSubmitLoaderComponent } from './form-submit-loader.component';

describe('FormSubmitLoaderComponent', () => {
  let component: FormSubmitLoaderComponent;
  let fixture: ComponentFixture<FormSubmitLoaderComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormSubmitLoaderComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormSubmitLoaderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
