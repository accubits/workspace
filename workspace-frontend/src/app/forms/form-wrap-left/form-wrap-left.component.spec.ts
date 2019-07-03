import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormWrapLeftComponent } from './form-wrap-left.component';

describe('FormWrapLeftComponent', () => {
  let component: FormWrapLeftComponent;
  let fixture: ComponentFixture<FormWrapLeftComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormWrapLeftComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormWrapLeftComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
