import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PhoneResponseComponent } from './phone-response.component';

describe('PhoneResponseComponent', () => {
  let component: PhoneResponseComponent;
  let fixture: ComponentFixture<PhoneResponseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PhoneResponseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PhoneResponseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
