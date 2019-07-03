import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FormIndiResponseComponent } from './form-indi-response.component';

describe('FormIndiResponseComponent', () => {
  let component: FormIndiResponseComponent;
  let fixture: ComponentFixture<FormIndiResponseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FormIndiResponseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FormIndiResponseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
