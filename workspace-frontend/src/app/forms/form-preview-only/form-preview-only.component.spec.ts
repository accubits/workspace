import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormPreviewOnlyComponent } from './form-preview-only.component';

describe('FormPreviewOnlyComponent', () => {
  let component: FormPreviewOnlyComponent;
  let fixture: ComponentFixture<FormPreviewOnlyComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormPreviewOnlyComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormPreviewOnlyComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
