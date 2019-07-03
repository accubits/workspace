import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PriceResponseComponent } from './price-response.component';

describe('PriceResponseComponent', () => {
  let component: PriceResponseComponent;
  let fixture: ComponentFixture<PriceResponseComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PriceResponseComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PriceResponseComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
