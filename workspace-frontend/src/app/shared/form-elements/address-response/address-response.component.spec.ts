import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddressResponseComponent } from './address-response.component';

describe('AddressResponseComponent', () => {
  let component: AddressResponseComponent;
  let fixture: ComponentFixture<AddressResponseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddressResponseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddressResponseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
