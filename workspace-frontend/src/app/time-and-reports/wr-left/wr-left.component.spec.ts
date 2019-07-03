import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WrLeftComponent } from './wr-left.component';

describe('WrLeftComponent', () => {
  let component: WrLeftComponent;
  let fixture: ComponentFixture<WrLeftComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WrLeftComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WrLeftComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
